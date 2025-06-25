import '../scss/DashboardContent.scss';
import DashboardFilter from './DashboardFilter';
import RecipeModal from '../Templates/RecipeModal';
import GridSort from '../Templates/GridSort';
import UserModal from '../Templates/UserModal';
import { Table } from 'antd';
import { useState } from 'react';
import { useDashboardLogic } from '../../hooks/useDashboardLogic';

export default function DashboardContent() {
    const [view, setView] = useState('list');
    const [searchTerm, setSearchTerm] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedItem, setSelectedItem] = useState(null);

    const {
        isUserDashboard,
        filteredData,
        columns,
        loading,
        refetchUsers,
        handleShowModal,
        userPagination,
        recipePagination,
        handleRecipeTableChange,
        handleUserTableChange,
        handleDelete,
        refetchRecipes
    } = useDashboardLogic(searchTerm, setIsModalOpen, setSelectedItem);

    const handleCloseModal = () => {
        if (isUserDashboard) {
            refetchUsers(userPagination.current,
                userPagination.pageSize
            );
        } else {
            refetchRecipes();
        }
        setIsModalOpen(false);
        setSelectedItem(null);
    };

    const handleDataChanged = () => {
        if (isUserDashboard) {
            refetchUsers(userPagination.current,
                userPagination.pageSize
            );
        } else {
            refetchRecipes();
        }
    };

    const handlePaginationChange = (page, pageSize) => {
        if (isUserDashboard) {
            handleUserTableChange({ current: page, pageSize: pageSize });
        } else {
            handleRecipeTableChange({ current: page, pageSize: pageSize });
        }
    };

    return (
        <div className="content-container">
            <DashboardFilter
                view={view}
                setView={setView}
                searchTerm={searchTerm}
                setSearchTerm={setSearchTerm}
                onDataChanged={handleDataChanged}
            />

            {view === 'list' ? (
                <Table
                    scroll={{ x: "max-content" }}
                    columns={columns}
                    dataSource={filteredData}
                    loading={loading}
                    pagination={isUserDashboard ? userPagination : recipePagination}
                    onChange={isUserDashboard ? handleUserTableChange : handleRecipeTableChange}
                    rowKey="key"
                />
            ) : (
                <GridSort
                    data={filteredData}
                    onEdit={handleShowModal}
                    onDelete={handleDelete}
                    loading={loading}
                    pagination={{
                        ...(isUserDashboard ? userPagination : recipePagination),
                        onChange: handlePaginationChange
                    }}
                />
            )}

            {isUserDashboard ? (
                <UserModal
                    open={isModalOpen}
                    onOk={handleCloseModal}
                    onCancel={handleCloseModal}
                    item={selectedItem}
                    mode="edit"
                />
            ) : (
                <RecipeModal
                    open={isModalOpen}
                    onOk={handleCloseModal}
                    onCancel={handleCloseModal}
                    item={selectedItem}
                    mode="edit"
                />
            )}
        </div>
    );
}