export const TransitionType = Object.freeze({

    SLIDE: 'S',
    FADE: 'F',
    ZOOM: 'Z',
});

export function getTransitionTypeLabel(value) {
    
    switch (value) {

        case TransitionType.SLIDE:
            return 'Slide';

        case TransitionType.FADE:
            return 'Fade';

        case TransitionType.ZOOM:
            return 'Zoom';
            
        default:
            return '';
    }
}
