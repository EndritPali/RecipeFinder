import { useState, useEffect, useCallback } from "react";
import api from "../Services/api";

export const useFetchRecipes = (onlyMine = false, paginate = true) => {
  const [recipes, setRecipes] = useState([]);
  const [loading, setLoading] = useState(false);
  const [pagination, setPagination] = useState({
    current: 1,
    pageSize: 10,
    total: 0,
    showSizeChanger: true,
    showTotal: (total) => `${total} Recipe(s) in total`,
  });

  const fetchRecipes = useCallback(
    async (page = 1, pageSize = 15) => {
      setLoading(true);
      try {
        const endpoint = onlyMine ? "v1/my-recipes" : "v1/recipes";
        const response = await api.get(endpoint, {
          params: paginate
            ? {
                page: page,
                per_page: pageSize,
              }
            : {},
        });

        const mapped = response.data.data.map((recipe) => ({
          key: recipe.id,
          recipetitle: recipe.title,
          category: recipe.category?.name || "Uncategorized",
          createdby: recipe.created_by || "Unknown",
          date: new Date(recipe.created_at).toLocaleDateString(),
          shortdescription: recipe.short_description,
          ingredients: recipe.ingredients,
          preparation: recipe.instructions,
          preptime: recipe.preparation_time,
          servings: recipe.servings,
          rating: recipe.rating,
          cooktime: recipe.cooking_time,
          image: recipe.image_url,
        }));

        setRecipes(mapped);

        if (paginate) {
          setPagination((prev) => ({
            ...prev,
            current: response.data.meta.current_page,
            total: response.data.meta.total,
            pageSize: response.data.meta.per_page,
          }));
        } else {
          setPagination((prev) => ({
            ...prev,
            current: 1,
            total: response.data.data.length,
            pageSize: response.data.data.length,
          }));
        }
      } catch (error) {
        console.error("Error fetching recipes:", error);
      } finally {
        setLoading(false);
      }
    },
    [onlyMine, paginate]
  );

  const handleTableChange = useCallback(
    (paginationConfig) => {
      if (paginate) {
        fetchRecipes(paginationConfig.current, paginationConfig.pageSize);
      }
    },
    [fetchRecipes, paginate]
  );

  useEffect(() => {
    fetchRecipes(1, 10);
  }, [fetchRecipes]);

  return {
    recipes,
    loading,
    fetchRecipes,
    paginate,
    pagination,
    handleTableChange,
  };
};
