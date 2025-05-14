import { Modal, Card, Button, message } from 'antd';
import { StarFilled, HeartOutlined, HeartFilled } from '@ant-design/icons';
import '../Scss/RecipeDetailsModal.scss';
import { useSavedRecipes } from '../hooks/useSavedRecipes';
import { useState, useEffect } from 'react';

export default function RecipeDetailsModal({ open, onOk, onCancel, recipe }) {
    const [isSaved, setIsSaved] = useState(false);
    const { checkIfSaved, saveRecipe, unsaveRecipe } = useSavedRecipes();
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        if (recipe && open) {
            checkSavedStatus();
        }
    }, [recipe, open]);

    const checkSavedStatus = async () => {
        if (!recipe) return;

        try {
            const saved = await checkIfSaved(recipe.key);
            setIsSaved(saved);
        } catch (error) {
            console.error('Error checking saved status:', error);
        }
    };

    const handleSaveToggle = async () => {
        if (!recipe) return;

        setIsLoading(true);
        try {
            if (isSaved) {
                await unsaveRecipe(recipe.key);
                setIsSaved(false);
                message.success('Recipe removed from favorites');
            } else {
                await saveRecipe(recipe.key);
                setIsSaved(true);
                message.success('Recipe added to favorites');
            }
        } catch (error) {
            console.error('Error toggling save status:', error);
            message.error('Failed to update favorites');
        } finally {
            setIsLoading(false);
        }
    };

    if (!recipe) return null;

    const HeartIcon = isSaved ? HeartFilled : HeartOutlined;

    return (
        <Modal
            open={open}
            onOk={onOk}
            onCancel={onCancel}
            title="Recipe Info"
            className="details-modal"
            footer={[
                <Button
                    key="save"
                    onClick={handleSaveToggle}
                    loading={isLoading}
                    icon={<HeartIcon />}
                    type={isSaved ? "primary" : "default"}
                >
                    {isSaved ? 'Saved' : 'Save Recipe'}
                </Button>,
                <Button key="close" onClick={onCancel}>
                    Close
                </Button>
            ]}
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