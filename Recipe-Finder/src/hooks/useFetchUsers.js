import { useState, useEffect, useCallback } from 'react';
import api from '../Services/api';

export const useFetchUsers = () => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchUsers = useCallback(async () => {
    setLoading(true);
    try {
      const response = await api.get('api/v1/user');
      const mapped = response.data.data.map(user => ({
        key: user.id,
        username: user.username,
        email: user.email,
        role: user.role,
        date: user.created_at,
      }));
      setUsers(mapped);
    } catch (error) {
      console.error('Error fetching users:', error);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchUsers(); 
  }, [fetchUsers]);

  return { users, loading, fetchUsers }; 
};
