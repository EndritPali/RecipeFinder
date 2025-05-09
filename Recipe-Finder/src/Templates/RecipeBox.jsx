import ImageDisplay from '../assets/salad-resized.png'
import StarShape from '../assets/star-filled.svg'
import '../Scss/Recipe-Box.scss'


export default function RecipeBox({ recipePlate, saladName, saladIngredients, saladRating }) {
    return (
        <>
            <div className="recipe-box">
                <div className="recipe-box__image">
                    <img src={recipePlate} alt="image-display" />
                </div>
                <div className="recipe-box__display">
                    <div className="recipe-box__info">
                        <h4>{saladName.split(' ').slice(0, 2).join(' ')}...</h4>
                        <div className="recipe-box__info-rating">
                            <p>{saladIngredients.slice(0, 2).join(', ')}...</p>
                            <span className="rating"><img src={StarShape} alt="star-shape" /><p>{saladRating}</p></span>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )

}