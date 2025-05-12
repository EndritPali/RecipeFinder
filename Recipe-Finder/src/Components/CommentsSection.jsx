import { useFetchComments } from '../hooks/useFetchComments';
import '../Scss/Comment-Section.scss';
import CommentsTemplate from '../Templates/CommentTemplate';
import ProfileImage from '../assets/pasta-four.svg';
import circleRight from '../assets/circle-right.svg';
import { Skeleton } from 'antd';

export default function CommentsSection() {
    const { comments, loading } = useFetchComments();

    return (
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
                            profile={ProfileImage}
                            name={comment.name}
                            likes={comment.likes}
                            date={comment.date}
                        />
                    ))
                )}
            </div>
        </div>
    );
}
