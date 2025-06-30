import { useMutation, useQueryClient } from "@tanstack/react-query";
import api from "../../../lib/api/api";
import { message } from "antd";

export function useCommentMutations() {
    const queryClient = useQueryClient();

    const addComment = useMutation({
        mutationFn: async (description) => {
            return api.post('v1/comments', { description })
        },
        onSuccess: () => {
            message.success('Comment added successfully!');
            queryClient.invalidateQueries({ queryKey: ['comments'] });
        },
        onError: () => {
            message.error('Failed to add comment.')
        }
    });

    const editComment = useMutation({
        mutationFn: async ({ id, description }) => {
            return api.put(`v1/comments/${id}`, { description })
        },
        onSuccess: () => {
            message.success('Comment updated successfully!')
            queryClient.invalidateQueries({ queryKey: ['comments'] })
        },
        onError: () => {
            message.error('Failed to edit comment.')
        }
    })

    const deleteComment = useMutation({
        mutationFn: async (id) => {
            return api.delete(`v1/comments/${id}`)
        },
        onSuccess: () => {
            message.success('Comment deleted successfully!')
            queryClient.invalidateQueries({ queryKey: ['comments'] })
        },
        onError: () => {
            message.error('Failed to delete comment.')
        }
    })

    const toggleLike = useMutation({
        mutationFn: async ({ id, action }) => {
            return api.post(`v1/comments/${id}/like`, { action });
        },
        onSuccess: (_, { action }) => {
            message.success(`Comment ${action}d successfully!`);
            queryClient.invalidateQueries({ queryKey: ['comments'] });
        },
        onError: () => {
            message.error('Failed to toggle like');
        }
    });

    return { addComment, editComment, deleteComment, toggleLike };
}