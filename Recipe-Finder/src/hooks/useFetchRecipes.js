import { useState, useEffect, useCallback } from 'react';
import api from '../Services/api';

export const useFetchRecipes = (onlyMine = false) => {
  const [recipes, setRecipes] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchRecipes = useCallback(async () => {
    setLoading(true);
    try {
      const endpoint = onlyMine ? 'v1/my-recipes' : 'v1/recipes';
      const response = await api.get(endpoint);
      const mapped = response.data.data.map(recipe => ({
        key: recipe.id,
        recipetitle: recipe.title,
        category: recipe.category?.name || 'Uncategorized',
        createdby: recipe.created_by || 'Unknown',
        date: new Date(recipe.created_at).toLocaleDateString(),
        shortdescription: recipe.short_description,
        ingredients: recipe.ingredients,
        preparation: recipe.instructions,
        preptime: recipe.preparation_time,
        servings: recipe.servings,
        rating: recipe.rating,
        cooktime: recipe.cooking_time,
        image: recipe.image_url,
      }));
      setRecipes(mapped);
    } catch (error) {
      console.error('Error fetching recipes:', error);
    } finally {
      setLoading(false);
    }
  }, [onlyMine]);

  useEffect(() => {
    fetchRecipes();
  }, [fetchRecipes]);

  return { recipes, loading, fetchRecipes };
};
