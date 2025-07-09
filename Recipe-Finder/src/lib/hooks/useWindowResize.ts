import { useState, useEffect } from "react";
import { Breakpoints, WindowSizeFlags, defaultBreakpoints } from "@/types/window";

export default function useWindowResize(breakpoints: Breakpoints = {}) {
  const merged = { ...defaultBreakpoints, ...breakpoints };
  const [flags, setFlags] = useState(() => getFlags(window.innerWidth, merged));

  useEffect(() => {
    const handleResize = () => {
      setFlags(getFlags(window.innerWidth, merged));
    };
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, [merged]);

  return flags;
}

function getFlags(width: number, breakpoints: Required<Breakpoints>): WindowSizeFlags {
  return {
    isMobile: width < breakpoints.mobile,
    isTablet: width >= breakpoints.mobile && width < breakpoints.tablet,
    isDesktop: width >= breakpoints.tablet,
  };
}
