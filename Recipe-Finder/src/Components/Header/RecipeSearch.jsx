import { AutoComplete } from 'antd';

export default function RecipeSearch({ visible, options, onSearch, onSelect }) {
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