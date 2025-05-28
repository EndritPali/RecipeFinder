import { useState, useEffect, useCallback } from 'react';
import api from '../Services/api';

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
dayjs.extend(relativeTime);


export const useFetchUsers = () => {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);

  const fetchUsers = useCallback(async () => {
    setLoading(true);
    try {
      const response = await api.get('v1/users');
      const mapped = response.data.data.map(user => ({
        key: user.id,
        username: user.username,
        email: user.email,
        role: user.role,
        date: dayjs(user.created_at).format('DD-MM-YYYY'),
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
