import { useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/lib/api/api';
import { message } from 'antd';

interface UpdateUserArgs {
  id: string;
  payload: Record<string, any>;
}

interface ApiError {
  response?: {
    data?: {
      message?: string;
    };
  };
}

export const useUpdateUser = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ id, payload }: UpdateUserArgs) => {
      const { data } = await api.put(`v1/users/${id}`, payload);
      return data;
    },
    onSuccess: () => {
      message.success('Updated successfully');
      queryClient.invalidateQueries({ queryKey: ['currentUser'] });
    },
    onError: (error: ApiError) => {
      message.error(error.response?.data?.message || 'Update failed');
    },
  });
}; 