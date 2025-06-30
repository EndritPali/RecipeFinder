import { useMemo, useCallback, useState } from "react";
import { useLocation } from "react-router-dom";
import { useQueryClient } from "@tanstack/react-query";
import { useFetchRecipes } from "@/features/recipes/hooks/useFetchRecipes";
import { useFetchUsers } from "@/features/admin/hooks/useFetchUsers";
import { useDeleteRecipes } from "@/features/admin/hooks/useDeleteRecipes";
import { useDeleteUsers } from "@/features/admin/hooks/useDeleteUsers";
import { columns as recipeColumns } from "@/features/admin/data/Data";
import { columns as userColumns } from "@/features/admin/data/UserData";
import { useAuth } from "@/context/AuthContext";

export function useDashboardLogic(searchTerm, setIsModalOpen, setSelectedItem) {
  const location = useLocation();
  const queryClient = useQueryClient();
  const isUserDashboard = location.pathname === "/admin/users";

  const { user, isLoadingAuth } = useAuth();
  const userRole = user?.role;
  const isUser = userRole === "User";

  const [recipePage, setRecipePage] = useState(1);
  const [recipePageSize, setRecipePageSize] = useState(10);

  const {
    data: recipeData,
    isLoading: loadingRecipes,
  } = useFetchRecipes({
    onlyMine: isUser,
    page: recipePage,
    pageSize: recipePageSize,
    enabled: !isLoadingAuth,
  });

  const [userPage, setUserPage] = useState(1);
  const [userPageSize, setUserPageSize] = useState(10);

  const {
    data: userData,
    isLoading: loadingUsers,
  } = useFetchUsers(
    userPage,
    userPageSize,
    { enabled: !isLoadingAuth && isUserDashboard }
  );

  const loading = (isLoadingAuth && !user) || (isUserDashboard ? loadingUsers : loadingRecipes);

  const { mutate: deleteRecipe } = useDeleteRecipes();
  const { mutate: deleteUser } = useDeleteUsers();

  const refetchRecipes = useCallback(() => {
    queryClient.invalidateQueries({ queryKey: ['recipes'] });
  }, [queryClient]);

  const refetchUsers = useCallback(() => {
    queryClient.invalidateQueries({ queryKey: ['users'] })
  }, [queryClient])

  const handleShowModal = useCallback(
    (record) => {
      setSelectedItem(record);
      setIsModalOpen(true);
    },
    [setIsModalOpen, setSelectedItem]
  );

  const handleDelete = useCallback(
    async (id) => {
      const msg = isUserDashboard
        ? "Are you sure you want to delete this user?"
        : "Are you sure you want to delete this recipe?";

      if (window.confirm(msg)) {
        isUserDashboard ? deleteUser(id) : deleteRecipe(id);
      }
    },
    [deleteUser, deleteRecipe, isUserDashboard]
  );

  const handleRecipeTableChange = useCallback((paginationConfig) => {
    setRecipePage(paginationConfig.current);
    setRecipePageSize(paginationConfig.pageSize);
  }, []);

  const handleUserTableChange = useCallback((paginationConfig) => {
    setUserPage(paginationConfig.current);
    setUserPageSize(paginationConfig.pageSize);
  }, []);

  const columns = useMemo(() => {
    return isUserDashboard
      ? userColumns(handleShowModal, handleDelete)
      : recipeColumns(handleShowModal, handleDelete);
  }, [isUserDashboard, handleShowModal, handleDelete]);

  const dataSource = useMemo(() => {
    if (isLoadingAuth && !user) {
      return [];
    }
    if (isUserDashboard) {
      const users = userData?.users || [];
      return users.filter((user) => user.key !== user?.id);
    }
    return recipeData?.recipes || [];
  }, [isUserDashboard, userData, user, recipeData, isLoadingAuth]);

  const filteredData = useMemo(() => {
    return dataSource.filter((item) => {
      const term = isUserDashboard ? item.username : item.recipetitle;
      return (term || "").toLowerCase().includes(searchTerm.toLowerCase());
    });
  }, [dataSource, isUserDashboard, searchTerm]);

  const userPagination = {
    current: userData?.meta?.current_page || 1,
    pageSize: userData?.meta?.per_page || 10,
    total: userData?.meta?.total || 0,
    showSizeChanger: true,
    showTotal: (total) => `${total} User(s) in total`,
  };

  const recipePagination = {
    current: recipeData?.meta?.current_page || 1,
    pageSize: recipeData?.meta?.per_page || 10,
    total: recipeData?.meta?.total || 0,
    showSizeChanger: true,
    showTotal: (total) => `${total} Recipe(s) in total`,
  };

  return {
    isUserDashboard,
    filteredData,
    columns,
    loading,
    handleShowModal,
    handleUserTableChange,
    handleRecipeTableChange,
    userPagination,
    recipePagination,
    handleDelete,
    user,
    refetchRecipes,
    refetchUsers
  };
}
