import { useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../Services/api';
import { message } from 'antd';

export function useRecipeMutations() {
  const queryClient = useQueryClient();

  const createRecipe = useMutation({
    mutationFn: async (payload) => {
      const { data } = await api.post('v1/recipes', payload);
      return data;
    },
    onSuccess: () => {
      message.success('Recipe created successfully');
      queryClient.invalidateQueries({ queryKey: ['recipes'] });
    },
    onError: (error) => {
      message.error(error.response?.data?.message || 'Recipe creation failed');
    },
  });

  const updateRecipe = useMutation({
    mutationFn: async ({ id, payload }) => {
      const { data } = await api.put(`v1/recipes/${id}`, payload);
      return data;
    },
    onSuccess: () => {
      message.success('Recipe updated successfully');
      queryClient.invalidateQueries({ queryKey: ['recipes'] });
    },
    onError: (error) => {
      message.error(error.response?.data?.message || 'Recipe update failed');
    },
  });

  return { createRecipe, updateRecipe };
} 