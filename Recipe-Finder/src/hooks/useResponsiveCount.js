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
  if (width < 1175) return 3;     
  if (width < 2000) return 5; 
  return 7;                          
}
