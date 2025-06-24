import { useMutation, useQueryClient } from "@tanstack/react-query";
import api from "../Services/api";
import { message } from "antd";

export const useDeleteRecipes = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (recipeId) => {
      return api.delete(`v1/recipes/${recipeId}`);
    },
    onSuccess: () => {
      message.success("Recipe deleted successfully!");
      queryClient.invalidateQueries({ queryKey: ["recipes"] });
    },
    onError: (error) => {
      console.error("Error deleting recipe:", error);
      message.error("Failed to delete recipe.");
    },
  });
};
