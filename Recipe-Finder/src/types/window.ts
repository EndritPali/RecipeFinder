export type Breakpoints = {
  mobile?: number;
  tablet?: number;
  desktop?: number;
};

export type WindowSizeFlags = {
  isMobile: boolean;
  isTablet: boolean;
  isDesktop: boolean;
};

export const defaultBreakpoints: Required<Breakpoints> = {
  mobile: 765,
  tablet: 1024,
  desktop: 1024,
};
