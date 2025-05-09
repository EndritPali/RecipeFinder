import '../Scss/Comment-Section.scss';
import CommentsTemplate from "../Templates/CommentTemplate";
import ProfileImage from '../assets/pasta-four.svg'
import circleRight from '../assets/circle-right.svg'


export default function CommentsSection() {
    return (
        <>
            <div className="comments__wrapper">
                <div className="comments__header">
                    <h2>Comments (4)</h2>

                    <div className="comments__header-arrow">
                        <img src={circleRight} alt="circle-right" />
                    </div>
                </div>
                <div className="comments__section">
                    <CommentsTemplate comment={'Comment'} profile={ProfileImage} name={'user'} likes={'x ammount'} />
                    <CommentsTemplate comment={'Comment'} profile={ProfileImage} name={'user'} likes={'x ammount'} />
                    <CommentsTemplate comment={'Comment'} profile={ProfileImage} name={'user'} likes={'x ammount'} />
                    <CommentsTemplate comment={'Comment'} profile={ProfileImage} name={'user'} likes={'x ammount'} />
                </div>
            </div>
        </>
    )
}