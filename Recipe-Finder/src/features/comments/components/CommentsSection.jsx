import { useFetchComments } from '@/features/comments/hooks/useFetchComments';
import { useState } from 'react';
import '@/Scss/CommentSection.scss';
import CommentsTemplate from '@/features/comments/components/CommentTemplate';
import circleRight from '@/assets/circle-right.svg';
import { Button, Skeleton, Pagination } from 'antd';
import CreateCommentModal from '@/features/comments/components/CreateCommentModal';
import CommentButtons from '@/features/comments/components/CommentButtons.jsx';
import { useAuth } from '@/context/AuthContext';
import { useCommentMutations } from '../hooks/useCommentMutations';

export default function CommentsSection() {
    const [page, setPage] = useState(1);
    const [pageSize, setPageSize] = useState(3);
    const { data, isLoading: loading, refetch: refreshComments } = useFetchComments(page, pageSize);
    const { user, isAuthenticated } = useAuth();
    const { deleteComment, toggleLike } = useCommentMutations()

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedComment, setSelectedComment] = useState(null);

    const comments = data?.comments || [];
    const pagination = {
        current: data?.meta?.current_page || page,
        pageSize: data?.meta?.per_page || pageSize,
        total: data?.meta?.total || 0,
    };

    const handleEditComment = (comment) => {
        setSelectedComment(comment);
        setIsModalOpen(true);
    };

    const handleDeleteComment = async (commentId) => {
        deleteComment.mutate(commentId)
    };

    const handleOpenModal = () => {
        setSelectedComment(null);
        setIsModalOpen(true);
    };

    const handleCloseModal = () => {
        setIsModalOpen(false);
        setSelectedComment(null);
    };

    const handleToggleLike = async (commentId, isLiked) => {
        toggleLike.mutate({ id: commentId, action: isLiked ? 'unlike' : 'like' })
    };

    const handlePaginationChange = (page, pageSize) => {
        setPage(page);
        setPageSize(pageSize);
    };

    return (
        <>
            <div className="comments__wrapper">
                <div className="comments__header">
                    <h2>Comments ({pagination.total})</h2>
                    <div className="comments__header-arrow">
                        <img src={circleRight} alt="circle-right" />
                    </div>
                </div>

                <div className="comments__section">
                    {loading ? (
                        Array.from({ length: pageSize }).map((_, index) => (
                            <div key={index} style={{ width: 300, margin: '0 1rem' }}>
                                <Skeleton active paragraph={{ rows: 2 }} />
                            </div>
                        ))
                    ) : (
                        comments.map(comment => {
                            const isOwner = user?.id === comment.creator;
                            const isAdmin = user?.role === 'Admin';
                            const hasLiked = comment.userHasLiked || false;

                            return (
                                <CommentsTemplate
                                    key={comment.id}
                                    comment={comment.comment}
                                    name={comment.name}
                                    likes={comment.likes}
                                    date={comment.date}
                                    hasLiked={hasLiked}
                                    onLikeToggle={() => handleToggleLike(comment.id, hasLiked)}
                                    buttons={
                                        <CommentButtons
                                            isOwner={isOwner}
                                            isAdmin={isAdmin}
                                            onEdit={() => handleEditComment(comment)}
                                            onDelete={() => handleDeleteComment(comment.id)}
                                        />
                                    }
                                />
                            );
                        })
                    )}
                </div>

                <div className="comments__pagination">
                    <Button
                        onClick={handleOpenModal}
                        className="create-comment-button"
                        disabled={!isAuthenticated}
                    >
                        Comment
                    </Button>

                    <Pagination
                        current={pagination.current}
                        pageSize={pagination.pageSize}
                        total={pagination.total}
                        onChange={handlePaginationChange}
                        showSizeChanger
                    />
                </div>

            </div>

            <CreateCommentModal
                open={isModalOpen}
                onOk={handleCloseModal}
                onCancel={handleCloseModal}
                refreshComments={refreshComments}
                mode={selectedComment ? 'edit' : 'create'}
                comment={selectedComment}
            />
        </>
    );
}
