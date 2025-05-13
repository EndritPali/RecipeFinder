import { useFetchComments } from '../hooks/useFetchComments';
import { useEffect, useState } from 'react';
import '../Scss/Comment-Section.scss';
import CommentsTemplate from '../Templates/CommentTemplate';
import circleRight from '../assets/circle-right.svg';
import { Button, Skeleton } from 'antd';
import CreateCommentModal from '../Templates/CreateCommentModal';
import auth from '../Services/auth';

export default function CommentsSection() {
    const { comments, loading, refreshComments } = useFetchComments();
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isAuthenticated, setIsAuthenticated] = useState(false);

    const handleOpenModal = () => setIsModalOpen(true);
    const handleCloseModal = () => setIsModalOpen(false);

    useEffect(() => {
        setIsAuthenticated(auth.isAuthenticated())
    }, [])

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
                        comments.map(comment => (
                            <CommentsTemplate
                                key={comment.id}
                                comment={comment.comment}
                                name={comment.name}
                                likes={comment.likes}
                                date={comment.date}
                            />
                        ))
                    )}
                </div>

                <Button
                    onClick={handleOpenModal}
                    className='create-comment-button'
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
            />
        </>
    );
}
