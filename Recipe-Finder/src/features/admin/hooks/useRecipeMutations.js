import { useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api/api';
import { message } from 'antd';

const transformValuesToPayload = (values) => ({
  title: values.recipetitle,
  short_description: values.shortdescription,
  rating: Number(values.rating),
  category: values.category,
  image_url: values.image,
  instructions: values.preparation,
  ingredients: values.ingredients,
  preparation_time: Number(values.preptime),
  cooking_time: Number(values.cooktime),
  servings: Number(values.servings),
})

export function useRecipeMutations() {
  const queryClient = useQueryClient();

  const createRecipe = useMutation({
    mutationFn: (values) => {
      const payload = transformValuesToPayload(values);
      return api.post('v1/recipes', payload);
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
    mutationFn: ({ id, values }) => {
      const payload = transformValuesToPayload(values);
      return api.put(`v1/recipes/${id}`, payload);
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