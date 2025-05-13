import { useState, useEffect, useCallback } from 'react';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import api from '../Services/api';

dayjs.extend(relativeTime);


export const useFetchComments = () => {
  const [comments, setComments] = useState([]);
  const [loading, setLoading] = useState(false);
  const [refreshFlag, setRefreshFlag] = useState(0);

  const fetchComments = useCallback(async () => {
    setLoading(true);
    try {
      const response = await api.get('/comments'); 
      

      const mapped = response.data.data.map(comment => ({
        id: comment.id,
        comment: comment.description,
        name: comment.user_id || 'Anonymous',
        likes: comment.likes || 0,
        date: dayjs(comment.created_at).fromNow()
      }));

      setComments(mapped);
    } catch (error) {
      console.error('Error fetching comments:', error);
    } finally {
      setLoading(false);
    }
  }, []);

  const refreshComments = useCallback(() =>{
     setRefreshFlag(prev => prev + 1);
  }, [])

  useEffect(() => {
    fetchComments();
  }, [fetchComments, refreshFlag]);

  return { comments, loading, refreshComments };
};
