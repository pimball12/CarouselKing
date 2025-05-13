export const Status = Object.freeze({

    ACTIVE: 'A',
    INACTIVE: 'I',
});

export function getStatusLabel(value) {
    
    switch (value) {

        case Status.ACTIVE:
            return 'Active';

        case Status.INACTIVE:
            return 'Inactive';
            
        default:
            return '';
    }
}
