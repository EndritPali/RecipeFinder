import { AutoComplete } from 'antd';

export default function MobileSearch({ filteredOptions, handleSearch, handleSelect }) {
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