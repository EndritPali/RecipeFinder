import { useState, useEffect } from 'react';
import '@/Scss/RecipeBanner.scss';
import StarShape from '@/assets/star-filled.svg';

export default function RecipeBanner({ background, rating, header, subheader, onClick }) {
    const [windowWidth, setWindowWidth] = useState(window.innerWidth);

    useEffect(() => {
        const handleResize = () => {
            setWindowWidth(window.innerWidth);
        };

        window.addEventListener('resize', handleResize);
        return () => window.removeEventListener('resize', handleResize);
    }, []);

    const shouldTruncate = windowWidth < 1450;

    const displaySubheader = shouldTruncate
        ? subheader.split(' ').slice(0, 3).join(' ') + '...'
        : subheader;

    const displayHeader = shouldTruncate
        ? header.split(' ').slice(0, 3).join(' ') + '...'
        : header;

    return (
        <div className="recipe-banner" onClick={onClick}>
            <div className="recipe-banner__img">
                <img src={background} alt="Recipe background" />
            </div>
            <div className="recipe-banner__info">
                <div className="recipe-banner__intro">
                    <h2>{displayHeader}</h2>
                    <h3>
                        <i className="far fa-circle-check"></i> {displaySubheader}
                    </h3>
                </div>
                <div className="recipe-banner__rating">
                    <p>
                        <img src={StarShape} alt="Star rating" /> {rating}
                    </p>
                </div>
            </div>
        </div>
    );
}