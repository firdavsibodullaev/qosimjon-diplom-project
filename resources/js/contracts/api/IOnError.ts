import type { IError } from '@/contracts/api/IError';

export type IOnError = null | ((error: IError) => void);
