import { useState } from "react";
import api from "../Services/api"; 

export const useDeleteRecipes = (onSuccess) => {
  const [isDeleting, setIsDeleting] = useState(false);
  const [error, setError] = useState(null);

  const deleteRecipe = async (id) => {
    setIsDeleting(true);
    setError(null);

    try {
      await api.delete(`v1/recipes/${id}`);
      
      if (onSuccess) {
        onSuccess();
      }
    } catch (err) {
      setError(
        err.response?.data?.message || 
        err.message || 
        'An error occurred while deleting this recipe'
      );
      console.error('Delete recipe error:', err);
    } finally {
      setIsDeleting(false);
    }
  };

  return {
    deleteRecipe,
    isDeleting,
    error
  };
};