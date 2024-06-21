export interface ICalibrationType {
	applicant: { name: string };
	created_at: string;
	document: { url: string | null; name: string; size: string };
	checkerFactory: { name: string };
	checker?: { name: string } | null;
	status: { key: string; value: string };
	result?: { key: string; value: string } | null;
	react_document: { url: string | null; name: string; size: string };
	checked_at: string | null;
}
