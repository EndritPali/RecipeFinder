import { useState, useEffect } from 'react';

export default function useResponsiveCount() {
  const [count, setCount] = useState(getCount(window.innerWidth));

  useEffect(() => {
    const handleResize = () => {
      setCount(getCount(window.innerWidth));
    };

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  return count;
}

function getCount(width) {
  if (width < 768) return 2;      
  if (width < 1024) return 3;      
  return 5;                          
}
