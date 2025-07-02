import { useQuery } from "@tanstack/react-query";
import api from "@/lib/api/api";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import { UseFetchRecipesOptions, Recipe } from '@/types/recipe';
dayjs.extend(relativeTime);

const fetchRecipes = async ({ queryKey }: { queryKey: [string, UseFetchRecipesOptions] }) => {
  const [_key, { onlyMine, paginate, page, pageSize }] = queryKey;

  const endpoint = onlyMine ? "v1/my-recipes" : "v1/recipes";
  const params = paginate ? { page, per_page: pageSize } : {};

  const { data } = await api.get(endpoint, { params });

  const mappedRecipes = data.data.map((recipe: Recipe) => ({
    key: recipe.id,
    recipetitle: recipe.title,
    category: recipe.category?.name || "Uncategorized",
    createdby: recipe.created_by || "Unknown",
    date: dayjs(recipe.created_at).format("DD-MM-YYYY"),
    shortdescription: recipe.short_description,
    ingredients: recipe.ingredients,
    preparation: recipe.instructions,
    preptime: recipe.preparation_time,
    servings: recipe.servings,
    rating: recipe.rating,
    cooktime: recipe.cooking_time,
    image: recipe.image_url,
  }));

  return {
    recipes: mappedRecipes,
    meta: data.meta,
  };
};

export const useFetchRecipes = (options: UseFetchRecipesOptions = {}) => {
  const {
    onlyMine = false,
    paginate = true,
    page = 1,
    pageSize = 10,
    enabled = true,
  } = options;

  return useQuery({
    queryKey: ["recipes", { onlyMine, paginate, page, pageSize }],
    queryFn: fetchRecipes,
    placeholderData: (prev) => prev,
    enabled,
  });
};
