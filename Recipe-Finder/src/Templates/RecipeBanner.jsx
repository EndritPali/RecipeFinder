import '../Scss/Recipe-Banner.scss';
import StarShape from '../assets/star-filled.svg';

export default function RecipeBanner({ background, rating, header, subheader }) {
    const truncatedSubheader = subheader.split(' ').slice(0, 3).join(' ') + '...';

    return (
        <div className="recipe-banner">
            <div className="recipe-banner__img">
                <img src={background} alt="Recipe background" />
            </div>
            <div className="recipe-banner__info">
                <div className="recipe-banner__intro">
                    <h2>{header}</h2>
                    <h3>
                        <i className="far fa-circle-check"></i> {truncatedSubheader}
                    </h3>
                </div>
                <div className="recipe-banner__rating">
                    <p>
                        <img src={StarShape} alt="Star rating" /> {rating}
                    </p>
                </div>
            </div>
        </div>
    );
}
