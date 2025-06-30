import { useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api/api';
import { message } from 'antd';

export const useCreateUser = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (payload) => {
      const { data } = await api.post('v1/users', payload);
      return data;
    },
    onSuccess: () => {
      message.success('User created successfully');
      queryClient.invalidateQueries({ queryKey: ['users'] });
    },
    onError: (error) => {
      message.error(error.response?.data?.message || 'User creation failed');
    },
  });
}; 