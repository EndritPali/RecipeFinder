import '../Scss/Comment-Template.scss'
import { Avatar } from 'antd'
import { UserOutlined } from '@ant-design/icons';

export default function CommentsTemplate({ name, comment, likes, date }) {
    return (
        <>
            <div className="comment">
                <div className="comment__info comment__info--primary">
                    <div className="comment__profile">
                        <Avatar>
                            <UserOutlined />
                        </Avatar>
                        <h4>{name}</h4>
                    </div>
                    <p className='comment__text'>{comment}</p>
                    <div className="comment__likes">
                        <i className="far fa-heart"> <span>{likes}</span></i>
                        <p>{date}</p>
                    </div>
                </div>
                <div className="comment__info comment__info--secondary">
                    <i className="far fa-comment-dots"></i>
                </div>
            </div>
        </>
    )
}