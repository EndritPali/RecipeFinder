import { Modal, Card, Button, message, Space } from 'antd';
import { StarFilled, HeartOutlined, HeartFilled } from '@ant-design/icons';
import '@/Scss/RecipeDetailsModal.scss';
import { useSaveRecipe, useUnsaveRecipe, useCheckIfSaved } from '@/features/recipes/hooks/useSavedRecipes';
import { useAuth } from '@/context/AuthContext';
import { useEffect } from 'react';
import { useQueryClient } from '@tanstack/react-query';

export default function RecipeDetailsModal({ open, onOk, onCancel, recipe }: any) {
    const queryClient = useQueryClient();
    const auth = useAuth();
    const isAuthenticated = auth?.isAuthenticated ?? false;
    const recipeId = recipe?.key;

    const { data: isSaved, isLoading: isCheckingSaved } = useCheckIfSaved(recipeId, {
        enabled: !!recipeId && !!open && isAuthenticated,
    });

    const { mutate: saveRecipe, isPending: isSaving } = useSaveRecipe();
    const { mutate: unsaveRecipe, isPending: isUnsaving } = useUnsaveRecipe();

    useEffect(() => {
        if (!open) {
            queryClient.removeQueries({ queryKey: ['saved-status', recipeId] });
        }
    }, [open, recipeId, queryClient]);


    const handleSaveToggle = async () => {
        if (!recipe) return;

        const mutationOptions = {
            onSuccess: () => {
                queryClient.invalidateQueries({ queryKey: ['saved-status', recipeId] });
                queryClient.invalidateQueries({ queryKey: ['saved-recipes'] });
                message.success(isSaved ? 'Recipe removed from favorites' : 'Recipe added to favorites');
            },
            onError: () => {
                message.error('Failed to update favorites');
            }
        };

        if (isSaved) {
            unsaveRecipe(recipe.key, mutationOptions);
        } else {
            saveRecipe(recipe.key, mutationOptions);
        }
    };

    if (!recipe) return null;

    const HeartIcon = isSaved ? HeartFilled : HeartOutlined;
    const isLoading = isCheckingSaved || isSaving || isUnsaving;

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

            <Card title={<Space><i className="fas fa-book-open-reader"></i> Preparation:</Space>}>
                <p>{recipe.preparation || 'No preparation details available'}</p>
            </Card>

            <Card title={<Space><i className="fas fa-utensils"></i> Ingredients: </Space>}>

                {recipe.ingredients && Array.isArray(recipe.ingredients) ? (
                    <ul>
                        {recipe.ingredients.map((ingredient: any, index: any) => (
                            <li key={index}>{ingredient}</li>
                        ))}
                    </ul>
                ) : (
                    <p>No ingredients listed</p>
                )}
            </Card>

            <div className="details-modal-general-info">
                <Card title={<Space><i className="fas fa-clock"></i> Preparation time:</Space>}>
                    <p>{recipe.preptime ? `${recipe.preptime} Hour(s)` : 'Not specified'}</p>
                </Card>
                <Card title={<Space><i className="fas fa-clock"></i> Cooking time:</Space>}>
                    <p>{recipe.cooktime ? `${recipe.cooktime} Hour(s)` : 'Not specified'}</p>
                </Card>
                <Card title={<Space><i className="fas fa-users"></i> Servings:</Space>}>
                    <p>{recipe.servings ? `${recipe.servings} Serving(s)` : 'Not specified'}</p>
                </Card>
            </div>
        </Modal >
    );
}