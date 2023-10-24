export interface DropzoneFileUpload {
    progress: number;
    total: number;
    bytesSent: number;
    uuid: string;
    totalChunkCount?: number | undefined;
}

export interface DropzoneFile extends File {
    dataURL?: string | undefined;
    previewElement: HTMLElement;
    previewTemplate: HTMLElement;
    previewsContainer: HTMLElement;
    status: string;
    accepted: boolean;
    xhr?: XMLHttpRequest | undefined;
    upload?: DropzoneFileUpload | undefined;
    s3ObjectLocation: string;
}

export interface IS3SignerURLResponse {
    signature: {
        "Content-Type": string;
        acl: string;
        success_action_status: string;
        policy: string;
        "X-amz-credential": string;
        "X-amz-algorithm": string;
        "X-amz-date": string;
        "X-amz-signature": string;
        key: string;
    };
    postEndpoint: string;
}

export interface ICommonS3ImageRequest {
    key: string;
    extension: string;
    original: string;
}
export interface ICommonS3ReportRequest extends ICommonS3ImageRequest {
    id?: string;
    label: string;
    file?: string;
    file_original?: string;
    file_size?: string;
    vp_component_id?: string;
    is_delete: string;
}

export interface DropzoneOptions {
    url?: ((files: ReadonlyArray<DropzoneFile>) => string) | string | undefined;
    method?:
        | ((files: ReadonlyArray<DropzoneFile>) => string)
        | string
        | undefined;
    withCredentials?: boolean | undefined;
    timeout?: number | undefined;
    parallelUploads?: number | undefined;
    uploadMultiple?: boolean | undefined;
    chunking?: boolean | undefined;
    forceChunking?: boolean | undefined;
    chunkSize?: number | undefined;
    parallelChunkUploads?: boolean | undefined;
    retryChunks?: boolean | undefined;
    retryChunksLimit?: number | undefined;
    maxFilesize?: number | undefined;
    paramName?: string | undefined;
    createImageThumbnails?: boolean | undefined;
    maxThumbnailFilesize?: number | undefined;
    thumbnailWidth?: number | undefined;
    thumbnailHeight?: number | undefined;
    thumbnailMethod?: "contain" | "crop" | undefined;
    resizeWidth?: number | undefined;
    resizeHeight?: number | undefined;
    resizeMimeType?: string | undefined;
    resizeQuality?: number | undefined;
    resizeMethod?: "contain" | "crop" | undefined;
    filesizeBase?: number | undefined;
    maxFiles?: number | undefined;
    params?: {} | undefined;
    headers?: { [key: string]: string } | undefined;
    clickable?:
        | boolean
        | string
        | HTMLElement
        | (string | HTMLElement)[]
        | undefined;
    ignoreHiddenFiles?: boolean | undefined;
    acceptedFiles?: string | undefined;
    renameFilename?(name: string): string;
    autoProcessQueue?: boolean | undefined;
    autoQueue?: boolean | undefined;
    addRemoveLinks?: boolean | undefined;
    previewsContainer?: boolean | string | HTMLElement | undefined;
    hiddenInputContainer?: HTMLElement | undefined;
    capture?: string | undefined;

    dictDefaultMessage?: string | undefined;
    dictFallbackMessage?: string | undefined;
    dictFallbackText?: string | undefined;
    dictFileTooBig?: string | undefined;
    dictInvalidFileType?: string | undefined;
    dictResponseError?: string | undefined;
    dictCancelUpload?: string | undefined;
    dictCancelUploadConfirmation?: string | undefined;
    dictRemoveFile?: string | undefined;
    dictRemoveFileConfirmation?: string | undefined;
    dictMaxFilesExceeded?: string | undefined;
    dictUploadCanceled?: string | undefined;

    accept?(file: DropzoneFile, done: (error?: string | Error) => void): void;
    chunksUploaded?(
        file: DropzoneFile,
        done: (error?: string | Error) => void
    ): void;
    forceFallback?: boolean | undefined;
    fallback?(): void;

    drop?(e: DragEvent): void;
    dragstart?(e: DragEvent): void;
    dragend?(e: DragEvent): void;
    dragenter?(e: DragEvent): void;
    dragover?(e: DragEvent): void;
    dragleave?(e: DragEvent): void;
    paste?(e: DragEvent): void;

    reset?(): void;

    addedfile?(file: DropzoneFile): void;
    addedfiles?(files: DropzoneFile[]): void;
    removedfile?(file: DropzoneFile): void;
    thumbnail?(file: DropzoneFile, dataUrl: string): void;

    error?(
        file: DropzoneFile,
        message: string | Error,
        xhr: XMLHttpRequest
    ): void;
    errormultiple?(
        files: DropzoneFile[],
        message: string | Error,
        xhr: XMLHttpRequest
    ): void;

    processing?(file: DropzoneFile): void;
    processingmultiple?(files: DropzoneFile[]): void;

    uploadprogress?(
        file: DropzoneFile,
        progress: number,
        bytesSent: number
    ): void;
    totaluploadprogress?(
        totalProgress: number,
        totalBytes: number,
        totalBytesSent: number
    ): void;

    sending?(file: DropzoneFile, xhr: XMLHttpRequest, formData: FormData): void;
    sendingmultiple?(
        files: DropzoneFile[],
        xhr: XMLHttpRequest,
        formData: FormData
    ): void;

    success?(file: DropzoneFile): void;
    successmultiple?(files: DropzoneFile[], responseText: string): void;

    canceled?(file: DropzoneFile): void;
    canceledmultiple?(file: DropzoneFile[]): void;

    complete?(file: DropzoneFile): void;
    completemultiple?(file: DropzoneFile[]): void;

    maxfilesexceeded?(file: DropzoneFile): void;
    maxfilesreached?(files: DropzoneFile[]): void;
    queuecomplete?(): void;

    transformFile?(
        file: DropzoneFile,
        done: (file: string | Blob) => void
    ): void;

    previewTemplate?: string | undefined;
}
export interface ICommonUploadedImages {
    id: string;
    image: string;
    image_original: string;
    image_thumbnail: string;
    image_size: string;
}