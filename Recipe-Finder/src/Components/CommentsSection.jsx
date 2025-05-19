import { useFetchComments } from '../hooks/useFetchComments';
import { useState } from 'react';
import '../Scss/CommentSection.scss';
import CommentsTemplate from '../Templates/CommentTemplate';
import circleRight from '../assets/circle-right.svg';
import { Button, Skeleton, message } from 'antd';
import CreateCommentModal from '../Templates/CreateCommentModal';
import CommentButtons from '../Templates/CommentButtons';
import api from '../Services/api';
import useAuth from '../hooks/useAuth';

export default function CommentsSection() {
    const { comments, loading, refreshComments } = useFetchComments();
    const { currentUser, isAuthenticated } = useAuth();

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedComment, setSelectedComment] = useState(null);

    const handleEditComment = (comment) => {
        setSelectedComment(comment);
        setIsModalOpen(true);
    };

    const handleDeleteComment = async (commentId) => {
        try {
            await api.delete(`/comments/${commentId}`);
            message.success('Comment deleted successfully');
            refreshComments();
        } catch (error) {
            console.error('Error deleting comment:', error);
            message.error('Failed to delete comment');
        }
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
        try {
            const action = isLiked ? 'unlike' : 'like';
            await api.post(`/comments/${commentId}/like`, { action });
            message.success(`Comment ${action}d successfully!`);
            refreshComments();
        } catch (error) {
            console.error('Error toggling like:', error);
            message.error('Failed to toggle like');
        }
    };

    return (
        <>
            <div className="comments__wrapper">
                <div className="comments__header">
                    <h2>Comments ({comments.length})</h2>
                    <div className="comments__header-arrow">
                        <img src={circleRight} alt="circle-right" />
                    </div>
                </div>

                <div className="comments__section">
                    {loading ? (
                        Array.from({ length: 3 }).map((_, index) => (
                            <div key={index} style={{ width: 300, margin: '0 1rem' }}>
                                <Skeleton active paragraph={{ rows: 2 }} />
                            </div>
                        ))
                    ) : (
                        comments.map(comment => {
                            const isOwner = currentUser?.id === comment.creator;
                            const isAdmin = currentUser?.role === 'Admin';
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

                <Button
                    onClick={handleOpenModal}
                    className="create-comment-button"
                    disabled={!isAuthenticated}
                >
                    Comment
                </Button>
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
