import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { isAxiosError } from "axios";
import api from "@/lib/api/api";
import { Recipe, UseFetchRecipesOptions } from '@/types/recipe';

const fetchSavedRecipes = async () => {
    const response = await api.get("v1/saved-recipes");
    const recipesData = response.data.data || [];
    return recipesData.map((recipe: Recipe) => ({
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
        recipeId: recipe.recipe_id,
    }));
};

export const useFetchSavedRecipes = (options: UseFetchRecipesOptions = {}) => {
    const { enabled = true } = options;
    return useQuery({
        queryKey: ['saved-recipes'],
        queryFn: fetchSavedRecipes,
        enabled,
    });
};

const saveRecipe = async (recipeId: Recipe) => {
    const { data } = await api.post("v1/saved-recipes", { recipe_id: recipeId });
    return data;
};

export const useSaveRecipe = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: saveRecipe,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['saved-recipes'] });
        },
    });
};

const unsaveRecipe = async (recipeId: Recipe) => {
    const { data } = await api.delete(`v1/saved-recipes/${recipeId}`);
    return data;
};

export const useUnsaveRecipe = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: unsaveRecipe,
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['saved-recipes'] });
        },
    });
};

const checkIfSaved = async (recipeId: Recipe) => {
    try {
        await api.get(`v1/saved-recipes/${recipeId}`);
        return true;
    } catch (error) {
        if (isAxiosError(error) && error.response && error.response.status === 404) {
            return false;
        }
        console.error("Error checking saved status:", error);
        return false;
    }
};

export const useCheckIfSaved = (recipeId: Recipe, options = {}) => {
    return useQuery({
        queryKey: ['saved-status', recipeId],
        queryFn: () => checkIfSaved(recipeId),
        ...options,
    });
};
