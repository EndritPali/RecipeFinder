import { useState } from "react";
import api from "../Services/api"; 

export const useDeleteUsers = (onSuccess) => {
  const [isDeleting, setIsDeleting] = useState(false);
  const [error, setError] = useState(null);

  const deleteUser = async (id) => {
    setIsDeleting(true);
    setError(null);

    try {
      await api.delete(`/user/${id}`);
      
      if (onSuccess) {
        onSuccess();
      }
    } catch (err) {
      setError(
        err.response?.data?.message || 
        err.message || 
        'An error occurred while deleting this user'
      );
      console.error('Delete user error:', err);
    } finally {
      setIsDeleting(false);
    }
  };

  return {
    deleteUser,
    isDeleting,
    error
  };
};