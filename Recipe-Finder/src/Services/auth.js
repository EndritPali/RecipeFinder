import api from './api';

const getCurrentUser = async () => {
  try {
    const response = await api.get('v1/auth/me');
    return response.data;
  } catch (error) {
    console.log(error)
    return null;
  }
};

const isAuthenticated = () => !!localStorage.getItem('token');

export default {
  getCurrentUser,
  isAuthenticated, 
};