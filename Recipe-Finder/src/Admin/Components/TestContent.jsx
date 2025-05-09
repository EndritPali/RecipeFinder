import '../scss/DashboardContent.scss'
import DashboardFilter from './DashboardFilter';
import RecipeModal from '../Templates/RecipeModal';
import RecipeGrid from '../Templates/GridSort';
import UserModal from '../Templates/UserModal';
import { Table } from 'antd';
import { columns as recipeColumns } from '../Data/Data';
import { columns as userColumns } from '../Data/UserData';
import { useLocation } from 'react-router-dom';
import { useState, useMemo, useCallback } from 'react';
import { useFetchRecipes } from '../../hooks/useFetchRecipes';
import { useFetchUsers } from '../../hooks/useFetchUsers';
import { useDeleteRecipes } from '../../hooks/useDeleteRecipes';

export default function DashboardContent() {
    const location = useLocation();
    const [view, setView] = useState('list');
    const [searchTerm, setSearchTerm] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedItem, setSelectedItem] = useState(null);

    const isUserDashboard = location.pathname === '/admin/users';

    const { recipes, loading: loadingRecipes, fetchRecipes } = useFetchRecipes();
    const { users, loading: loadingUsers, fetchUsers } = useFetchUsers();
    const loading = isUserDashboard ? loadingUsers : loadingRecipes;

    const handleShowModal = useCallback((record) => {
        setSelectedItem(record);
        setIsModalOpen(true);
    }, []);

    const handleCloseModal = () => {
        fetchUsers();
        setIsModalOpen(false);
        setSelectedItem(null);
    };

    const { deleteRecipe } = useDeleteRecipes(() => {
        fetchRecipes();
    });

    const handleDelete = useCallback((id) => {


        if (isUserDashboard ? window.confirm('Are you sure you want to delete this recipe?') : window.confirm('Are you sure you want to delete this user?')) {
            deleteRecipe(id);
        }
    }, [deleteRecipe, isUserDashboard]);

    const columns = useMemo(() => {
        return isUserDashboard
            ? userColumns(handleShowModal)
            : recipeColumns(handleShowModal, handleDelete);
    }, [isUserDashboard, handleShowModal, handleDelete]);

    const filteredData = useMemo(() => {
        const sourceData = isUserDashboard ? users : recipes;
        return sourceData.filter(item => {
            const searchField = isUserDashboard ? item.username : item.recipetitle;
            return (searchField || '').toLowerCase().includes(searchTerm.toLowerCase());
        });
    }, [recipes, users, searchTerm, isUserDashboard]);

    return (
        <div className="content-container">
            <DashboardFilter
                view={view}
                setView={setView}
                searchTerm={searchTerm}
                setSearchTerm={setSearchTerm}
            />

            {view === 'list' ? (
                <Table
                    columns={columns}
                    dataSource={filteredData}
                    loading={loading}
                    pagination={{
                        showTotal: (total) => `${total} items in total`,
                    }}
                    rowKey="key"
                />
            ) : (
                <RecipeGrid
                    data={filteredData}
                    onEdit={handleShowModal}
                    onDelete={handleDelete}
                />
            )}

            {isUserDashboard ? (
                <UserModal
                    open={isModalOpen}
                    onOk={() => {
                        fetchUsers();
                        handleCloseModal();
                    }}
                    onCancel={handleCloseModal}
                    item={selectedItem}
                    mode="edit"
                />
            ) : (
                <RecipeModal
                    open={isModalOpen}
                    onOk={handleCloseModal}
                    onCancel={handleCloseModal}
                    mode="edit"
                    item={selectedItem}
                />
            )}
        </div>
    );
}