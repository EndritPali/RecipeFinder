import '../Scss/Recipe-Banner.scss'
import StarShape from '../assets/star-filled.svg'

export default function RecipeBanner({ background, rating, header, subheader }) {
    return (
        <>
            <div className="recipe-banner">
                <div className="recipe-banner__img">
                    <img src={background} alt="bg-img" />
                </div>
                <div className="recipe-banner__info">
                    <div className="recipe-banner__intro">
                        <h2>{header}</h2>
                        <h3><i className="far fa-circle-check"></i> {subheader.split(' ').slice(0, 3).join(' ')}...</h3>
                    </div>
                    <div className="recipe-banner__rating">
                        <p><img src={StarShape} alt="star" />{rating}</p>
                    </div>
                </div>
            </div>
        </>
    )
}