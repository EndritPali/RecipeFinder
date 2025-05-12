import { useState, useEffect, useCallback } from 'react';
import api from '../Services/api';

export const useFetchComments = () => {
  const [comments, setComments] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchComments = useCallback(async () => {
    setLoading(true);
    try {
      const response = await api.get('/comments'); 
      console.log('Fetched comments:', response.data); 

      const mapped = response.data.data.map(comment => ({
        id: comment.id,
        comment: comment.description,
        name: comment.user_id || 'Anonymous',
        likes: comment.likes || 0,
        date: comment.created_at
      }));

      setComments(mapped);
    } catch (error) {
      console.error('Error fetching comments:', error);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchComments();
  }, [fetchComments]);

  return { comments, loading };
};
