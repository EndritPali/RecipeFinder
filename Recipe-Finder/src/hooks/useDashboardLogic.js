import { useMemo, useCallback } from 'react';
import { useLocation } from 'react-router-dom';
import { useFetchRecipes } from './useFetchRecipes';
import { useFetchUsers } from './useFetchUsers';
import { useDeleteRecipes } from './useDeleteRecipes';
import { useDeleteUsers } from './useDeleteUsers';
import { columns as recipeColumns } from '../Admin/Data/Data';
import { columns as userColumns } from '../Admin/Data/UserData';
import useAuth from '../hooks/useAuth'; 

export function useDashboardLogic(searchTerm, setIsModalOpen, setSelectedItem) {
    const location = useLocation();
    const isUserDashboard = location.pathname === '/admin/users';

    const { currentUser, isAuthenticated } = useAuth(); 
    const userRole = currentUser?.role;
    const isUser = userRole === 'User';

    const { recipes, loading: loadingRecipes, fetchRecipes } = useFetchRecipes(isUser);
    const { users, loading: loadingUsers, fetchUsers } = useFetchUsers();

    const loading = !isAuthenticated || (isUserDashboard ? loadingUsers : loadingRecipes);

    const { deleteRecipe } = useDeleteRecipes(fetchRecipes);
    const { deleteUser } = useDeleteUsers(fetchUsers);

    const handleShowModal = useCallback((record) => {
        setSelectedItem(record);
        setIsModalOpen(true);
    }, [setIsModalOpen, setSelectedItem]);

    const handleDelete = useCallback(async (id) => {
        const msg = isUserDashboard
            ? 'Are you sure you want to delete this user?'
            : 'Are you sure you want to delete this recipe?';

        if (window.confirm(msg)) {
            try {
                isUserDashboard ? await deleteUser(id) : await deleteRecipe(id);
            } catch (err) {
                console.error("Deletion error:", err);
                alert("Something went wrong during deletion");
            }
        }
    }, [deleteUser, deleteRecipe, isUserDashboard]);

    const columns = useMemo(() => {
        return isUserDashboard
            ? userColumns(handleShowModal, handleDelete)
            : recipeColumns(handleShowModal, handleDelete);
    }, [isUserDashboard, handleShowModal, handleDelete]);

    const dataSource = useMemo(() =>{
        return isUserDashboard
        ? users.filter(user => user.key !== currentUser?.id)
        : recipes;
    }, [isUserDashboard, users, recipes, currentUser?.id]);

    const filteredData = useMemo(() => {
        return dataSource.filter(item => {
            const term = isUserDashboard ? item.username : item.recipetitle;
            return (term || '').toLowerCase().includes(searchTerm.toLowerCase());
        });
    }, [dataSource, isUserDashboard, searchTerm]);

    return {
        isUserDashboard,
        filteredData,
        columns,
        loading,
        fetchUsers,
        fetchRecipes,
        handleShowModal,
        handleDelete,
        currentUser
    };
}
