import api from './api';

const auth = {
  isAuthenticated: () => localStorage.getItem('token') !== null,
  
  getCurrentUser: async () => {
    try {
      const response = await api.get('api/v1/auth/me');
      return response.data;
    } catch (error) {
      console.error('Error fetching user data:', error);
      return null;
    }
  },
  
  hasRole: async (requiredRoles) => {
    if (!auth.isAuthenticated()) return false;
    
    try {
      const user = await auth.getCurrentUser();
      if (!user) return false;
      
      const roles = Array.isArray(requiredRoles) ? requiredRoles : [requiredRoles];
      return roles.includes(user.role);
    } catch (error) {
      console.error('Role check failed:', error);
      return false;
    }
  }
};

export default auth;