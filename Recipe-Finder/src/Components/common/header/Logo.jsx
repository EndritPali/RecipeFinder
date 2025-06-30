// import { useState } from 'react';

export default function Logo({ setShowMobileSearch }) {
    return (
        <div className="header__logo">
            <div className="header__logo-box">
                <i
                    className="fas fa-bars"
                    onClick={() => setShowMobileSearch(prev => !prev)}
                ></i>
            </div>
            <h1><span>Recipe</span> finder</h1>
        </div>
    );
}