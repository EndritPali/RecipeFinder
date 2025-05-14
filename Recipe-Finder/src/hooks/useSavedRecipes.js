import { useState, useEffect, useCallback } from 'react';
import api from '../Services/api';

const savedRecipesEvent = new EventTarget();

export const useSavedRecipes = () => {
  const [savedRecipes, setSavedRecipes] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchSavedRecipes = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await api.get('/saved-recipes');
      const mappedRecipes = response.data.map(item => ({
        key: item.recipe.id,
        recipetitle: item.recipe.title,
        category: item.recipe.category?.name || 'Uncategorized',
        shortdescription: item.recipe.short_description,
        ingredients: item.recipe.ingredients,
        preparation: item.recipe.instructions,
        preptime: item.recipe.preparation_time,
        servings: item.recipe.servings,
        rating: item.recipe.rating,
        cooktime: item.recipe.cooking_time,
        image: item.recipe.image_url,
        savedId: item.id,
      }));
      setSavedRecipes(mappedRecipes);
    } catch (error) {
      console.error('Error fetching saved recipes:', error);
      setError('Failed to load saved recipes');
    } finally {
      setLoading(false);
    }
  }, []);

  const checkIfSaved = useCallback(async (recipeId) => {
    try {
      const response = await api.get(`/saved-recipes/${recipeId}`);
      return response.data.saved;
    } catch (error) {
      console.error('Error checking saved status:', error);
      return false;
    }
  }, []);

  const saveRecipe = useCallback(async (recipeId) => {
    try {
      await api.post('/saved-recipes', { recipe_id: recipeId });
      savedRecipesEvent.dispatchEvent(new Event('changed'));
      return true;
    } catch (error) {
      console.error('Error saving recipe:', error);
      return false;
    }
  }, []);

  const unsaveRecipe = useCallback(async (recipeId) => {
    try {
      await api.delete(`/saved-recipes/${recipeId}`);
      setSavedRecipes(prevRecipes => prevRecipes.filter(recipe => recipe.key !== recipeId));
      savedRecipesEvent.dispatchEvent(new Event('changed'));
      return true;
    } catch (error) {
      console.error('Error removing saved recipe:', error);
      return false;
    }
  }, []);

  useEffect(() => {
    const handleChange = () => {
      fetchSavedRecipes();
    };
    
    savedRecipesEvent.addEventListener('changed', handleChange);
    
    return () => {
      savedRecipesEvent.removeEventListener('changed', handleChange);
    };
  }, [fetchSavedRecipes]);

  useEffect(() => {
    const user = localStorage.getItem('user');
    if (user) {
      fetchSavedRecipes();
    }
  }, [fetchSavedRecipes]);

  return {
    savedRecipes,
    loading,
    error,
    fetchSavedRecipes,
    checkIfSaved,
    saveRecipe,
    unsaveRecipe
  };
};