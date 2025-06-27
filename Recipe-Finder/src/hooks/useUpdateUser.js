import { useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../Services/api';
import { message } from 'antd';

export const useUpdateUser = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, payload }) => {
      const { data } = await api.put(`v1/users/${id}`, payload);
      return data;
    },
    onSuccess: () => {
      message.success('Updated successfully');
      queryClient.invalidateQueries({ queryKey: ['currentUser'] });
    },
    onError: (error) => {
      message.error(error.response?.data?.message || 'Update failed');
    },
  });
}; 