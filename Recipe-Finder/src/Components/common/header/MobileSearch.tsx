import { AutoComplete } from 'antd';

interface MobileSearchProps {
    filteredOptions: any;
    handleSearch: (value: string) => void;
    handleSelect: (value: string, option: any) => void;
    showMobileSearch: boolean;
}

export default function MobileSearch({ filteredOptions, handleSearch, handleSelect, showMobileSearch }: MobileSearchProps) {
    if (!showMobileSearch) return null;

    return (
        <div className="header__search--mobile">
            <i className="fas fa-search"></i>
            <AutoComplete
                style={{ width: 300 }}
                options={filteredOptions}
                onSearch={handleSearch}
                onSelect={handleSelect}
                placeholder="Search for recipes"
                autoFocus
                filterOption={false}
            />
        </div>
    );
}