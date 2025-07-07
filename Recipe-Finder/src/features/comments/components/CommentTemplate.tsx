import '@/Scss/CommentTemplate.scss'
import { TemplateProps } from '@/types/comment';

export default function CommentsTemplate({
    name,
    comment,
    likes,
    date,
    buttons,
    onLikeToggle,
    hasLiked,
    avatar
}: TemplateProps) {
    return (
        <div className="comment">
            <div className="comment__info comment__info--primary">
                <div className="comment__profile">
                    {avatar}
                    <h4>{name}</h4>
                </div>

                <p className='comment__text'>{comment}</p>

                <div className="comment__likes" onClick={onLikeToggle} style={{ cursor: 'pointer' }}>
                    <i className={hasLiked ? "fas fa-heart-liked" : "far fa-heart"}>
                        <span>{likes}</span>
                    </i>
                    <p>{date}</p>
                </div>
            </div>

            <div className="comment__info comment__info--secondary">
                <i className="far fa-comment-dots"></i>
                {buttons}
            </div>
        </div>
    )
}
