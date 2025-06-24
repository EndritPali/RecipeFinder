import { useMutation, useQueryClient } from "@tanstack/react-query";
import api from "../Services/api";
import { message } from "antd";

export const useDeleteUsers = () => {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: (userId) => {
            return api.delete(`v1/users/${userId}`);
        },
        onSuccess: () => {
            message.success("User deleted successfully!");
            queryClient.invalidateQueries({ queryKey: ["users"] });
        },
        onError: (error) => {
            console.error("Error deleting user:", error);
            message.error("Failed to delete user.");
        },
    });
};