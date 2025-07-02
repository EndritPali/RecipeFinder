import { useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/lib/api/api";
import { message } from "antd";

interface ApiError {
  response?: {
    data?: {
      message?: string;
    };
  };
}

export const useDeleteRecipes = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (recipeId: number) => {
      return api.delete(`v1/recipes/${recipeId}`);
    },
    onSuccess: () => {
      message.success("Recipe deleted successfully!");
      queryClient.invalidateQueries({ queryKey: ["recipes"] });
    },
    onError: (error: ApiError) => {
      console.error("Error deleting recipe:", error);
      message.error(error.response?.data?.message || "Failed to delete recipe.");
    },
  });
};
