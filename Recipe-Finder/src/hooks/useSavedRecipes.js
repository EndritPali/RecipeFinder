import { useState, useEffect, useCallback } from "react";
import api from "../Services/api";

const savedRecipesEvent = new EventTarget();

export const useSavedRecipes = () => {
  const [savedRecipes, setSavedRecipes] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchSavedRecipes = useCallback(async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await api.get("v1/saved-recipes");
      const recipesData = response.data.data || [];
      const mappedRecipes = recipesData.map((recipe) => ({
        key: recipe.id,
        recipetitle: recipe.title,
        category: recipe.category?.name || "Uncategorized",
        shortdescription: recipe.short_description,
        ingredients: recipe.ingredients,
        preparation: recipe.instructions,
        preptime: recipe.preparation_time,
        servings: recipe.servings,
        rating: recipe.rating,
        cooktime: recipe.cooking_time,
        image: recipe.image_url,
        savedId: recipe.id,
      }));
      setSavedRecipes(mappedRecipes);
    } catch (error) {
      console.error("Error fetching saved recipes:", error);
      setError("Failed to load saved recipes");
    } finally {
      setLoading(false);
    }
  }, []);

  const checkIfSaved = useCallback(async (recipeId) => {
    try {
      await api.get(`v1/saved-recipes/${recipeId}`);
      return true;
    } catch (error) {
      if (error.response && error.response.status === 404) {
        return false;
      }
      console.error("Error checking saved status:", error);
      return false;
    }
  }, []);

  const saveRecipe = useCallback(async (recipeId) => {
    try {
      await api.post("v1/saved-recipes", { recipe_id: recipeId });
      savedRecipesEvent.dispatchEvent(new Event("changed"));
      return true;
    } catch (error) {
      console.error("Error saving recipe:", error);
      return false;
    }
  }, []);

  const unsaveRecipe = useCallback(async (recipeId) => {
    try {
      await api.delete(`v1/saved-recipes/${recipeId}`);
      setSavedRecipes((prevRecipes) =>
        prevRecipes.filter((recipe) => recipe.key !== recipeId)
      );
      savedRecipesEvent.dispatchEvent(new Event("changed"));
      return true;
    } catch (error) {
      console.error("Error removing saved recipe:", error);
      return false;
    }
  }, []);

  useEffect(() => {
    const handleChange = () => {
      fetchSavedRecipes();
    };

    savedRecipesEvent.addEventListener("changed", handleChange);

    const user = localStorage.getItem("user");
    if (user) {
      fetchSavedRecipes();
    }

    return () => {
      savedRecipesEvent.removeEventListener("changed", handleChange);
    };
  }, [fetchSavedRecipes]);

  useEffect(() => {
    const user = localStorage.getItem("user");
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
    unsaveRecipe,
  };
};
