// import { useState } from 'react';

interface LogoProps {
  setShowMobileSearch: React.Dispatch<React.SetStateAction<boolean>>;
}

export default function Logo({ setShowMobileSearch }: LogoProps) {
    return (
        <div className="header__logo">
            <div className="header__logo-box">
                <i
                    className="fas fa-bars"
                    onClick={() => setShowMobileSearch((prev: boolean) => !prev)}
                ></i>
            </div>
            <h1><span>Recipe</span> finder</h1>
        </div>
    );
}