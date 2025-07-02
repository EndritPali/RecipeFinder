export interface UseFetchRecipesOptions {
    onlyMine?: boolean;
    paginate?: boolean;
    page?: number;
    pageSize?: number;
    enabled?: boolean;
}

export interface Recipe {
    id: number;
    title: string;
    category?: { name: string };
    created_by?: string;
    created_at: string;
    short_description: string;
    ingredients: string[];
    instructions: string;
    preparation_time: number;
    servings: number;
    rating: number;
    cooking_time: number;
    image_url: string;
    recipe_id: number;
    key: number;
}

export interface MappedRecipe {
    key: number;
    recipetitle: string;
    category: string;
    createdby: string;
    date: string;
    shortdescription: string;
    ingredients: string[];
    preparation: string;
    preptime: number;
    servings: number;
    rating: number;
    cooktime: number;
    image: string;
}

export interface RecipeBoxProps {
    recipePlate: string;
    saladName: string;
    saladIngredients: string[];
    saladRating: number;
    onClick: () => void;
};

