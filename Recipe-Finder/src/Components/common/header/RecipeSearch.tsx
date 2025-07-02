import { AutoComplete } from 'antd';

interface RecipeSearchProps {
    visible: boolean;
    options: any;
    onSearch: (value: string) => void;
    onSelect: (value: string, option: any) => void;
}

export default function RecipeSearch({ visible, options, onSearch, onSelect }: RecipeSearchProps) {
    if (!visible) return null;

    return (
        <AutoComplete
            style={{ width: 300 }}
            options={options}
            onSearch={onSearch}
            onSelect={onSelect}
            placeholder="Search for recipes"
            autoFocus
            filterOption={false}
        />
    );
}