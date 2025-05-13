import { Modal, Card } from 'antd';
import { StarFilled } from '@ant-design/icons';
import '../Scss/RecipeDetailsModal.scss';

export default function RecipeDetailsModal({ open, onOk, onCancel, recipe }) {
    if (!recipe) return null;

    return (
        <Modal
            open={open}
            onOk={onOk}
            onCancel={onCancel}
            title="Recipe Info"
            className="details-modal"
        >
            <div
                className="details-modal__header"
                style={{
                    backgroundImage: `url(${recipe.image})`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                }}
            >
                <div className="details-modal__header-info">
                    <h1>{recipe.recipetitle}</h1>
                    <p>{recipe.shortdescription}</p>
                </div>

                <div className="details-modal-rating">
                    <div className="rating-text">
                        <p>{recipe.rating}</p>
                        <StarFilled className="star" />
                    </div>
                </div>
            </div>

            <Card title="Preparation:">
                <p>{recipe.preparation || 'No preparation details available'}</p>
            </Card>

            <Card title="Ingredients:">
                {recipe.ingredients && Array.isArray(recipe.ingredients) ? (
                    <ul>
                        {recipe.ingredients.map((ingredient, index) => (
                            <li key={index}>{ingredient}</li>
                        ))}
                    </ul>
                ) : (
                    <p>No ingredients listed</p>
                )}
            </Card>

            <div className="details-modal-general-info">
                <Card title="Preparation time:">
                    <p>{recipe.preptime ? `${recipe.preptime} Hours` : 'Not specified'}</p>
                </Card>
                <Card title="Cooking time:">
                    <p>{recipe.cooktime ? `${recipe.cooktime} Hours` : 'Not specified'}</p>
                </Card>
                <Card title="Servings:">
                    <p>{recipe.servings || 'Not specified'}</p>
                </Card>
            </div>
        </Modal>
    );
}